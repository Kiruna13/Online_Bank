package com.example.banqueenligne

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.View
import android.view.Window
import android.widget.Button
import com.google.android.flexbox.FlexboxLayout

class DetailBeneficiaire : AppCompatActivity(), View.OnClickListener {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        setContentView(R.layout.activity_detail_beneficiaire)

        val b = findViewById<Button>(R.id.btn_virement)
        b.setOnClickListener(this)

        //actionbar
        val actionbar = supportActionBar
        //set actionbar title
        actionbar!!.title = "Détails bénéficiaire"
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
            R.id.btn_virement -> startActivity(Intent(this@DetailBeneficiaire, MakeVirement::class.java))
        }
    }
}