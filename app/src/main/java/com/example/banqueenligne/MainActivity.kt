package com.example.banqueenligne

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.Window
import androidx.fragment.app.Fragment
import com.example.banqueenligne.fragment.AccountsFragment
import com.example.banqueenligne.fragment.VirementFragment
import com.example.banqueenligne.fragment.CardsFragment
import com.google.android.material.bottomnavigation.BottomNavigationView

class MainActivity : AppCompatActivity() {
    private val accountsFragment = AccountsFragment()
    private val virementFragment = VirementFragment()
    private val cardsFragment = CardsFragment()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.activity_main)

        val bundle = Bundle()
        bundle.putString("user", intent.getStringExtra("user"))
        accountsFragment.arguments = bundle
        virementFragment.arguments = bundle
        cardsFragment.arguments = bundle
        replaceFragment(accountsFragment)

        val navigation = findViewById<BottomNavigationView>(R.id.bottom_navigation)
        navigation.setOnNavigationItemSelectedListener {
            when(it.itemId){
                R.id.fragment_accounts -> replaceFragment(accountsFragment)
                R.id.fragment_virement -> replaceFragment(virementFragment)
                R.id.fragment_cards -> replaceFragment(cardsFragment)
            }
            true
        }
    }

    private fun replaceFragment(fragment: Fragment){
        if(fragment != null){
            val transaction = supportFragmentManager.beginTransaction()
            transaction.replace(R.id.fragment_container, fragment)
            transaction.commit()
        }
    }
}