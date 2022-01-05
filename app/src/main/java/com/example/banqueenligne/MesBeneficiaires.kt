package com.example.banqueenligne

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.Window
import com.google.android.flexbox.FlexboxLayout

class MesBeneficiaires : AppCompatActivity(), View.OnClickListener {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.activity_mes_beneficiaires)

        val b = findViewById<FlexboxLayout>(R.id.displayBeneficiaire)
        b.setOnClickListener(this)

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Mes bénéficiaires"
        //set back button
        actionbar.setDisplayHomeAsUpEnabled(true)
        actionbar.setDisplayHomeAsUpEnabled(true)
    }

    override fun onSupportNavigateUp(): Boolean {
        onBackPressed()
        return true
    }

    override fun onClick(view: View) {
        when (view.getId()) {
            R.id.displayBeneficiaire -> startActivity(Intent(this@MesBeneficiaires, DetailBeneficiaire::class.java))
        }
    }
}